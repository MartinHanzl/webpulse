import EventItem from "./event-item";
import styles from './event-list.module.css'

export default function EventList(props) {
    const {items} = props;
    return (
        <ul className={styles.list}>
            {items.map(event => <EventItem key={event.id} id={event.id} location={event.location} date={event.date} title={event.title} image={event.image} />)}
        </ul>
    )
}