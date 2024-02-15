'use client';
import styles from "@/components/main-header/nav-link.module.css";
import Link from "next/link";
import {usePathname} from "next/navigation";

export default function NavLink(props)
{
    const path = usePathname();
    return (
        <Link href={props.href} className={path.startsWith(props.href) ? `${styles.link} ${styles.active}` : styles.link}>{props.children}</Link>
    )
}