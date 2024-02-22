import {getAllEvents} from "../../dummy-data";
import EventList from "../../components/events/event-list";

export default function EventsPage() {
    const events = getAllEvents();
    return (
        <main>
            <h1>Events page</h1>
            <EventList items={events} />
        </main>
    )
}