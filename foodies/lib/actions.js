'use server';

import {saveMeal} from "@/lib/meals";
import {redirect} from "next/navigation";

export async function shareMeal(FormData) {
    const meal = {
        title: FormData.get('title'),
        summary: FormData.get('summary'),
        creator_email: FormData.get('email'),
        creator: FormData.get('name'),
        instructions: FormData.get('instructions'),
        image: FormData.get('image')
    }

    await saveMeal(meal);
    await redirect('/meals');
}