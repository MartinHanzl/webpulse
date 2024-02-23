import {useEffect, useState} from "react";
import useSWR from "swr";

export default function LastSales() {
    const { data, error } = useSWR(
        'https://quickstart-1612785053126-default-rtdb.firebaseio.com/sales.json',
        (url) => fetch(url).then(res => res.json())
    );
    const [sales, setSales] = useState();

    useEffect(() => {
        if(data) {
            const transformedSales = [];

            for (const key in data) {
                transformedSales.push({id: key, username: data[key].username, volume: data[key].volume})
            }
            setSales(transformedSales);
        }
    }, [data]);

    if (!data) {
        return <p>Loading...</p>;
    }

    if (error || !sales) {
        return <p>Failed to load</p>;
    }
    return (
        <main>
            <h1>
                Last sales
            </h1>
            <ul>
                {sales.map(sale => <li key={sale.id}>{sale.username} - ${sale.volume}</li>)}
            </ul>
        </main>
    )
}

