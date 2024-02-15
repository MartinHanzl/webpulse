import Link from "next/link";
import Logo from "@/assets/logo.png"

export default function MainHeaderComponent() {
    return (
        <header>
            <Link href="/">
                <img src={Logo.src} />
                NextLevel Food
            </Link>
            <nav>
                <ul>
                    <li>
                        <Link href="/meals">Browse meals</Link>
                    </li>
                    <li>
                        <Link href="/community">Foodies community</Link>
                    </li>
                </ul>
            </nav>
        </header>
    )
}