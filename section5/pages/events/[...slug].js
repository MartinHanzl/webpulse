import {useRouter} from "next/router";
import {getFilteredEvents} from "../../dummy-data";
import EventList from "../../components/events/event-list";

export default function SingleEventSlugPage() {
    const router = useRouter();

    const filteredData = router.query.slug;

    if (!filteredData) {
        return <p className="center">No event found!</p>
    }

    const filteredYear = +filteredData[0];
    const filteredMonth = +filteredData[1];

    if (isNaN(filteredYear) || isNaN(filteredMonth)) {
        return <p className="center">Invalid URL!</p>
    }

    const events = getFilteredEvents({
        year: filteredMonth,
        month: filteredMonth
    });

    if (!events) {
        return <p className="center">No events!</p>
    }

    console.log(events);

    return (
        <main>
            <EventList items={events}/>
        </main>
    )
}