import {useRouter} from "next/router";
export default function BlogPost() {
    const router = useRouter();
    console.log(router.query);

    return (
        <div>

        </div>
    )
}