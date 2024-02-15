import Link from "next/link";
import Logo from "@/assets/logo.png"
import styles from './main-header.module.css'
import Image from "next/image";
import MainHeaderBackground from "@/components/main-header/main-header-background";
import NavLink from "@/components/main-header/nav-link";

export default function MainHeaderComponent() {
    return (
        <>
            <MainHeaderBackground/>
            <header className={styles.header}>
                <Link href="/" className={styles.logo}>
                    <Image src={Logo} alt="Logo image" priority/>
                    NextLevel Food
                </Link>
                <nav className={styles.nav}>
                    <ul>
                        <li>
                            <NavLink href="/meals">Browse meals</NavLink>
                        </li>
                        <li>
                            <NavLink href="/community">Foodies community</NavLink>
                        </li>
                    </ul>
                </nav>
            </header>
        </>
    )
}