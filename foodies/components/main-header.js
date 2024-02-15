import Link from "next/link";
import Logo from "@/assets/logo.png"
import styles from './main-header.module.css'
import Image from "next/image";

export default function MainHeaderComponent() {
    return (
        <header className={styles.header}>
            <Link href="/" className={styles.logo}>
                <Image src={Logo} alt="Logo image" priority />
                NextLevel Food
            </Link>
            <nav className={styles.nav}>
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