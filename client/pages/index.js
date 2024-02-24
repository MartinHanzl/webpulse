import {Inter} from "next/font/google";
import HomepageHeroSection from "@/components/homepage/hero";

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

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
            {/*<div class="bg-slate-950">
                <div class="mx-auto w-auto px-2 lg:px-8 bg-slate-700 py-24 rounded-3xl">
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3">
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-slate-400">Transactions every 24 hours</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-slate-200 sm:text-5xl">44 million</dd>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-slate-400">Assets under holding</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-slate-200 sm:text-5xl">$119 trillion</dd>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-slate-400">New users annually</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-slate-200 sm:text-5xl">46,000</dd>
                        </div>
                    </dl>
                </div>
            </div>*/}
        </>
    );
}