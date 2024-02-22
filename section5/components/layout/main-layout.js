import {Fragment} from "react";
import Header from "./main-header";

export default function Layout(props) {
    return (
        <Fragment>
            <Header />
            <main>
                {props.children}
            </main>
        </Fragment>
    )
}