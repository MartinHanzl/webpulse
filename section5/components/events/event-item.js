import Link from "next/link";
import Image from "next/image";

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
        <li key={props.id}>
            <Image src={'/' + image} width={150} height={150} alt={title} />
            <div>
                <div>
                    <h2>{title}</h2>
                    <div>
                        <time>{humanReadableDate}</time>
                    </div>
                    <div>
                        <address>{formattedAddress}</address>
                    </div>
                </div>
                <div>
                    <Link href={exploreLink}>Explore event</Link>
                </div>
            </div>
        </li>
    )
}