import Link from "next/link";
import {getFeaturedEvents} from "../dummy-data";
export default function HomePage() {
    const featuredEvents = getFeaturedEvents();
    return (
        <main>
            <h1>Homepage</h1>
            <ul>
                {featuredEvents.map(event => {

                })}
            </ul>
        </main>
    )
}