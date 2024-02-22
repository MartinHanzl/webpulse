import Link from "next/link";
import {getFeaturedEvents} from "../dummy-data";
import EventList from "../components/events/event-list";
export default function HomePage() {
    const featuredEvents = getFeaturedEvents();
    return (
        <main>
            <h1>Homepage</h1>
            <EventList items={featuredEvents} />
        </main>
    )
}