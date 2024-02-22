import {useRouter} from "next/router";
import {getFilteredEvents} from "../../dummy-data";
import EventList from "../../components/events/event-list";

export default function SingleEventSlugPage() {
    const router = useRouter();

    const filteredData = router.query.slug;

    if (!filteredData) {
        return <p className="center">No event found!</p>
    }

    const filteredYear = Number(filteredData[0]);
    const filteredMonth = Number(filteredData[1]);

    if (isNaN(filteredYear) || isNaN(filteredMonth)) {
        return <p className="center">Invalid URL!</p>
    }

    const events = getFilteredEvents({
        year: filteredMonth,
        month: filteredMonth
    });

    if(!events) {
        return <p className="center">No events!</p>
    }

    return (
        <main>
            <h1>Single event SLUG</h1>
            <EventList items={events} />
        </main>
    )
}