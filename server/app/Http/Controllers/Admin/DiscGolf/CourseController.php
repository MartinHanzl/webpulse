<?php

namespace App\Http\Controllers\Admin\DiscGolf;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DiscGolf\CourseResource;
use App\Models\DiscGolf\Course;
use App\Models\DiscGolf\CourseLayout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $query = Course::query()
            ->with(['layouts'])
            ->whereRelation('sites', 'site_id', $siteId);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('orderBy') && $request->has('orderWay')) {
            $query->orderBy($request->get('orderBy'), $request->get('orderWay'));
        } else {
            $query->orderBy('position');
        }

        if ($request->has('paginate')) {
            $items = $query->paginate($request->get('paginate'));

            return Response::json([
                'data' => CourseResource::collection($items->items()),
                'total' => $items->total(),
                'perPage' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'lastPage' => $items->lastPage(),
            ]);
        }

        return Response::json(CourseResource::collection($query->get()));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $item = Course::with(['layouts.holes'])
            ->whereRelation('sites', 'site_id', $siteId)
            ->find($id);

        if (! $item) {
            App::abort(404);
        }

        return Response::json(CourseResource::make($item));
    }

    public function store(Request $request, ?int $id = null)
    {
        if ($id) {
            $item = Course::find($id);
            if (! $item) {
                App::abort(404);
            }
        } else {
            $item = new Course;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'layouts' => 'nullable|array',
            'layouts.*.name' => 'required|string|max:255',
            'layouts.*.holes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), 400);
        }

        DB::beginTransaction();
        try {
            $item->fill($request->all());
            $item->save();

            $item->saveSites($item, $request->get('sites', []));

            $this->syncLayouts($item, $request->get('layouts', []));

            DB::commit();

            return Response::json(CourseResource::make(
                $item->fresh(['layouts.holes'])
            ));
        } catch (\Throwable $e) {
            DB::rollBack();

            return Response::json(['error' => 'An error occurred while saving the course.'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Course::find($id);
        if (! $item) {
            App::abort(404);
        }

        $item->delete();

        return Response::json();
    }

    public function reorder(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $allowedIds = Course::whereRelation('sites', 'site_id', $siteId)->pluck('id')->toArray();

        DB::transaction(function () use ($request, $allowedIds) {
            foreach ($request->get('order', []) as $entry) {
                if (! in_array((int) $entry['id'], $allowedIds, true)) {
                    continue;
                }
                Course::where('id', $entry['id'])->update(['position' => $entry['position']]);
            }
        });

        return Response::json();
    }

    /**
     * Upsert layouts + their holes; delete removed ones. Recompute holes_count and par from holes.
     */
    private function syncLayouts(Course $course, array $layouts): void
    {
        $keptLayoutIds = [];

        foreach ($layouts as $index => $layoutData) {
            $layout = isset($layoutData['id'])
                ? CourseLayout::where('course_id', $course->id)->find($layoutData['id'])
                : null;

            if (! $layout) {
                $layout = new CourseLayout;
                $layout->course_id = $course->id;
            }

            $holes = $layoutData['holes'] ?? [];
            $par = 0;
            $meters = 0;
            $hasMeters = false;
            foreach ($holes as $hole) {
                $par += (int) ($hole['par'] ?? 0);
                if (isset($hole['meters']) && $hole['meters'] !== null && $hole['meters'] !== '') {
                    $meters += (int) $hole['meters'];
                    $hasMeters = true;
                }
            }

            $layout->name = $layoutData['name'];
            $layout->holes_count = count($holes);
            $layout->par = $par;
            $layout->total_meters = $hasMeters ? $meters : ($layoutData['total_meters'] ?? null);
            $layout->position = $index;
            $layout->save();

            $keptLayoutIds[] = $layout->id;

            // Sync holes for this layout
            $keptHoleIds = [];
            foreach ($holes as $holeIndex => $hole) {
                $holeModel = isset($hole['id'])
                    ? $layout->holes()->find($hole['id'])
                    : $layout->holes()->make();

                if (! $holeModel) {
                    $holeModel = $layout->holes()->make();
                }

                $holeModel->course_layout_id = $layout->id;
                $holeModel->number = $hole['number'] ?? ($holeIndex + 1);
                $holeModel->par = (int) ($hole['par'] ?? 3);
                $holeModel->meters = isset($hole['meters']) && $hole['meters'] !== '' ? (int) $hole['meters'] : null;
                $holeModel->save();
                $keptHoleIds[] = $holeModel->id;
            }
            $layout->holes()->whereNotIn('id', $keptHoleIds)->delete();
        }

        $course->layouts()->whereNotIn('id', $keptLayoutIds)->delete();
    }
}
