import {useRouter} from 'next/router';

export default function DetailPage() {
    const router = useRouter();
    console.log(router.pathname);
    console.log(router.query);

    return (
        <div>
            <h1>Detail page ID</h1>
            <p>{router.query.id}</p>
        </div>
    )
}