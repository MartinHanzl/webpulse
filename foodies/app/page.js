import Link from "next/link";

export default function Home() {
  return (
    <main>
      <h1 style={{ color: 'white', textAlign: 'center' }}>
        Time to get started!
      </h1>
        <p><Link href="/meals" style={{color: 'white'}}>Meals</Link></p>
        <p><Link href="/meals/share" style={{color: 'white'}}>Share meals</Link></p>
        <p><Link href="/community" style={{color: 'white'}}>Community</Link></p>
    </main>
  );
}
