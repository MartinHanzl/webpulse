import {getAllEvents} from "../../dummy-data";
import EventList from "../../components/events/event-list";
import EventsSearch from "../../components/events/events-search";

export default function EventsPage() {
    const events = getAllEvents();
    return (
        <main>
            <h1>Events page</h1>
            <EventsSearch />
            <EventList items={events} />
        </main>
    )
}