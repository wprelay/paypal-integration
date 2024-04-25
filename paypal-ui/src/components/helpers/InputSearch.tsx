import React, { useRef, useState } from "react";
import { Input } from "../ui/input";
import { Button } from "../ui/button"

type InputSearchProps = {
    search: string,
    setSearch: any,
    onclick: any
}

const InputSearch = ({ search, setSearch, onclick }: InputSearchProps) => {

    return (
        <div className="wrp-items-stretch wrp-flex wrp-justify-between wrp-gap-2">
            <div className='wrp-relative'>
                <i className="wpr wpr-search wrp-text-lg wrp-absolute wrp-top-3 wrp-left-4"></i>
                <Input
                    className="wrp-text-primary !wrp-px-12 !wrp-w-340px wrp-text-sm wrp-font-medium wrp-grow wrp-whitespace-nowrap wrp-outline-none"
                    placeholder="search" onChange={(e: any) => {
                        setSearch(e.target.value)
                    }} value={search}>
                </Input>
                {search ? (<i onClick={() => {
                    setSearch('')
                    onclick('')
                }}
                    className="wpr wpr-close wrp-text-lg wrp-cursor-pointer wrp-absolute wrp-top-3 wrp-right-4"></i>
                ) : null}
            </div>
            <div>
                <Button
                    className={`wrp-text-neutral-100 !wrp-h-10  wrp-text-sm wrp-font-semibold wrp-whitespace-nowrap wrp-justify-center wrp-items-stretch  wrp-px-4 pwrp-y-3 wrp-rounded-lg ${search.length ? '' : 'wrp-opacity-30'}`}
                    onClick={() => {
                        onclick(search)

                    }} disabled={!search.length}>
                    Search
                </Button>
            </div>
        </div>
    )
}

export default InputSearch;