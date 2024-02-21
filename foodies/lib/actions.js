'use server';

import {saveMeal} from "@/lib/meals";
import {redirect} from "next/navigation";

function isInvalidText(text) {
    return !text || text.trim() === '';
}

export async function shareMeal(FormData) {
    const meal = {
        title: FormData.get('title'),
        summary: FormData.get('summary'),
        creator_email: FormData.get('email'),
        creator: FormData.get('name'),
        instructions: FormData.get('instructions'),
        image: FormData.get('image')
    }

    if (
        isInvalidText(meal.title) ||
        isInvalidText(meal.summary) ||
        isInvalidText(meal.instruction) ||
        isInvalidText(meal.creator) ||
        isInvalidText(meal.creator_email) ||
        !meal.creator_email.includes('@') ||
        !meal.image || meal.image.size === 0
    ) {
        throw new Error('Invalid input');
    }

    await saveMeal(meal);
    await redirect('/meals');
}