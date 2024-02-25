import {Inter} from "next/font/google";
import HomepageHeroSection from "@/components/homepage/hero";

export async function generateMetadata() {
    return {
        title: 'Test'
    }
}

const inter = Inter({subsets: ["latin"]});
export default function Home() {
    return (
        <>
            <HomepageHeroSection />
        </>
    );
}