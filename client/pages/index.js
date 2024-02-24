import {Inter} from "next/font/google";
import HomepageHeroSection from "@/components/homepage/hero";
import i18next from './i18n'
import {Suspense} from "react";
import {useRouter} from "next/router";

const inter = Inter({subsets: ["latin"]});
export default function Home() {
    const router = useRouter();

    if (!router.locale) {
        languageHandler('cs');
    }
    languageHandler(router.locale);

    return (
        <Suspense fallback="loading">
            <HomepageHeroSection/>
        </Suspense>
    );
}

function languageHandler(lang) {
    i18next.changeLanguage(lang);
}
