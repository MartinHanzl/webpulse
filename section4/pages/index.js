import Link from 'next/link';

export default function Homepage() {
    return (
        <div>
            <h1>Ahoj</h1>
            <ul>
                <li>
                    <Link href="/portfolio">Portfolio</Link>
                </li>
                <li>
                    <Link href="/clients">Clients</Link>
                </li>
            </ul>
        </div>
    )
}