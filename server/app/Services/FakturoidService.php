<?php

namespace App\Services;

use Fakturoid\FakturoidManager;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class FakturoidService
{
    protected FakturoidManager $client;

    public function __construct()
    {
        $this->client = new FakturoidManager(
            new Client(),
            env('FAKTUROID_API_KEY', 'd8dbd51f6682dcab457ee1c38c9a5b1abc62b8f1'), //TODO: replace with real data
            env('FAKTUROID_API_SECRET', 'aff0321fcb0f74c8fd5fd1c70b39ec6d5db3bd6d'), //TODO: replace with real data
            'Webpulse dev <martas.hanzl@email.cz>'
        );
        $this->client->authClientCredentials();

        $user = $this->client->getUsersProvider()->getCurrentUser();
        $this->client->setAccountSlug($user->getBody()->accounts[0]->slug);
    }

    public function getSubjects()
    {
        $response = $this->client->getSubjectsProvider()->list();
        $subjects = $response->getBody()->subjects;

        return $subjects;
    }

    public function getSubject(int $id) {
        $response = $this->client->getSubjectsProvider()->get($id);
        $subject = $response->getBody()->subject;

        return $subject;
    }

}
