import {useRouter} from "next/router";
export default function ClientsPageId() {
    const router = useRouter();

    return (
        <div>
            <h1>Project of given client</h1>
        </div>
    )
}