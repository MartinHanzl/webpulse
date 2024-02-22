import {useRouter} from "next/router";

export default function SingleEventPage() {
    const router = useRouter();
    return (
        <main>
            <h1>Single event <strong>ID</strong></h1>
        </main>
    )
}