import {useRouter} from "next/router";
export default function ClientsPageId() {
    const router = useRouter();

    return (
        <div>
            <h1>Project client {router.query.id}</h1>
        </div>
    )
}