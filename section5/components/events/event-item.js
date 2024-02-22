import Link from "next/link";
import Image from "next/image";
import styles from './event-item.module.css';

export default function EventItem(props) {
    const {title, image, date, location, id} = props;
    const humanReadableDate = new Date(date).toLocaleDateString('en-US', {
        day: "numeric",
        month: "long",
        year: "numeric"
    });

    const formattedAddress = location.replace(', ', '\n');
    const exploreLink = `/events/${id}`;

    return (
        <li key={props.id} className={styles.item}>
            <img src={'/' + image} alt={title} className={styles.icon} />
            <div className={styles.content}>
                <div>
                    <h2>{title}</h2>
                    <div>
                        <time>{humanReadableDate}</time>
                    </div>
                    <div>
                        <address>{formattedAddress}</address>
                    </div>
                </div>
                <div className={styles.actions}>
                    <Link href={exploreLink}>Explore event</Link>
                </div>
            </div>
        </li>
    )
}