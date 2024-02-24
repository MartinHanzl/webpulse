import { Inter } from "next/font/google";
import HomepageHeroSection from "@/components/homepage/hero";
import './i18n';

const inter = Inter({ subsets: ["latin"] });

export default function Home() {
  return (
      <>
        <HomepageHeroSection />
      </>
  );
}
