import {Raleway} from 'next/font/google'
import './globals.css'

const raleway = Raleway({subsets: ['latin']})

export const metadata = {
    title: 'Web-pulse',
    description: 'Generated by create next app',
}

export default function RootLayout({children}) {
    return (
        <html lang="cs">
        <body className={raleway.className}>{children}</body>
        </html>
    )
}
