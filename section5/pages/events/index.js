import {getAllEvents} from "../../dummy-data";
import EventList from "../../components/events/event-list";
import EventsSearch from "../../components/events/events-search";
import {useRouter} from "next/router";

export default function EventsPage() {
    const events = getAllEvents();
        const router = useRouter();

    function findEventsHandler(year, month) {
        router.push(`/events/${year}/${month}`);
    }

    return (
        <main>
            <h1>All events page</h1>
            <EventsSearch onSearch={findEventsHandler()}/>
            <EventList items={events}/>
        </main>
    )
}