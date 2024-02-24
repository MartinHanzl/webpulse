import "@/styles/globals.css";
import Header from "@/components/layout/header";
import {Fragment} from "react";

export default function App({Component, pageProps}) {
    return (
        <>
            <Header/>
            <Fragment>
                <main className="lg:container mx-auto">
                    <Component {...pageProps} />
                </main>
            </Fragment>
        </>
    );
}
