import { useState } from 'react'

export const useInputSearch = () => {
    const [search, setSearch] = useState<string>('')
    const [searched, setIsSearched] = useState<boolean>(false);

    return { search, setSearch, searched, setIsSearched }
}


export default useInputSearch;