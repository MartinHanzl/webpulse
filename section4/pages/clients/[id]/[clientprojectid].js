import {useRouter} from "next/router";

export default function ClientsPageId() {
    const router = useRouter();
    console.log(router.query);
    return (
        <div>
            <h1>Project of given client of specific id</h1>
        </div>
    )
}