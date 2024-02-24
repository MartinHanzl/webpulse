import "@/styles/globals.css";
import Header from "@/components/layout/header";
import {Fragment} from "react";
import i18next from './i18n'
import {useRouter} from "next/router";

export default function App({Component, pageProps}) {
    const router = useRouter();

    if (!router.locale) {
        languageHandler('cs');
    }
    languageHandler(router.locale);

    return (
        <>
            <Header lang={router.locale}/>
            <Fragment>
                <main className="lg:container mx-auto">
                    <Component {...pageProps} />
                </main>
            </Fragment>
        </>
    );
}

function languageHandler(lang) {
    i18next.changeLanguage(lang);
}
