import EventList from '../components/events/event-list';
import {getFeaturedEvents} from "../helpers/api-utils";

function HomePage(props) {
  return (
    <div>
      <EventList items={props.events} />
    </div>
  );
}

export default HomePage;

export async function getStaticProps(context) {
    const events = await getFeaturedEvents();

    return {
        props: {
            events: events
        },
        revalidate: 10
    }
}