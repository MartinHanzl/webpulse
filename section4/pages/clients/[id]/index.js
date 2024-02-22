import {useRouter} from "next/router";
export default function ClientsPageId() {
    const router = useRouter();

    function loadProjectHandler() {
        router.push('/clients');
    }

    return (
        <div>
            <h1>Project client {router.query.id}</h1>
            <button onClick={loadProjectHandler}>Load project A</button>
        </div>
    )
}