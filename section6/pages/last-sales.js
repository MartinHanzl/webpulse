import {useEffect, useState} from "react";

export default function LastSales() {
    const [sales, setSales] = useState();
    const [isLoading, setIsLoading] = useState(false);
    useEffect(() => {
        setIsLoading(true);
        fetch('https://quickstart-1612785053126-default-rtdb.firebaseio.com/sales.json')
            .then(response => response.json())
            .then(data => {
                const transformedSales = [];

                for (const key in data) {
                    transformedSales.push({id: key, username: data[key].username, volume: data[key].volume})
                }
                setSales(transformedSales);
                console.log(sales);
                setIsLoading(false);
            });
    }, []);

    if (isLoading) {
        return <p>Loading...</p>;
    }

    if (!sales) {
        return <p>No data yet</p>;
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

