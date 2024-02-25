import {Disclosure, Menu, Transition} from '@headlessui/react'
import {Fragment} from "react";
import Image from 'next/image';
import useSWR from "swr";
import {useEffect, useState} from 'react'

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function HeaderLanguageSwitcher(props) {
    const locale = props.lang;

    const [languages, setLanguages] = useState();

    const {data, error} = useSWR(
        'http://localhost:8000/api/languages/list/' + props.lang,
        (url) => fetch(url).then(res => res.json())
    );

    useEffect(() => {
        if (data) {
            const languages = [];

            for (const key in data) {
                languages.push({
                    id: key,
                    ...data[key],
                });
            }

            setLanguages(languages);
        }
    }, [data]);

    if (languages) {
        return (
            <Menu as="div" className="relative ml-16">
                <div>
                    <Menu.Button
                        className="flex rounded-full bg-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-4 focus:ring-offset-slate-950">
                        <img
                            className="h-8 w-8 rounded-full"
                            src={`/images/${locale}.svg`}
                            alt=""
                        />
                    </Menu.Button>
                </div>
                <Transition
                    as={Fragment}
                    enter="transition ease-out duration-100"
                    enterFrom="transform opacity-0 scale-95"
                    enterTo="transform opacity-100 scale-100"
                    leave="transition ease-in duration-75"
                    leaveFrom="transform opacity-100 scale-100"
                    leaveTo="transform opacity-0 scale-95"
                >
                    <Menu.Items
                        className="absolute right-0 z-10 mt-4 w-32 origin-top-right border-2 rounded-md bg-slate-950 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        {languages.map((language) => (
                            <div key={language.id}>
                                <Menu.Item>
                                    {({active}) => (
                                        <a
                                            href={`/${language.locale}`}
                                            className={classNames(active ? 'bg-gray-100 text-slate-950' : '', 'block px-4 py-2 text-sm text-slate-200')}
                                        >
                                            {language.name}
                                        </a>
                                    )}
                                </Menu.Item>
                            </div>
                        ))}
                    </Menu.Items>
                </Transition>
            </Menu>
        )
    }
}
