import {Inter} from "next/font/google";
import HomepageHeroSection from "@/components/homepage/hero";
import './i18n';
import {Suspense} from "react";

const inter = Inter({subsets: ["latin"]});

export default function Home() {
    return (
        <Suspense fallback="loading">
            <HomepageHeroSection/>
        </Suspense>
    );
}
