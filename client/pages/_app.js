import "@/styles/globals.css";
import Header from "@/components/layout/header";
import {Fragment} from "react";

export default function App({Component, pageProps}) {
    return (
        <>
            <Header/>
            <Fragment>
                <main>
                    <Component {...pageProps} />
                </main>
            </Fragment>
        </>
    );
}
