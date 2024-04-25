import React, {useState} from "react";
import {Card} from "../components/ui/card";
import {axiosClient} from "../components/axios";
import {toastrError} from "../ToastHelper";
import {useLocalState} from "../zustand/localState";
import Select from 'react-select';
import useInputSearch from "../components/customHooks/useInputSearch";
import InputSearch from "../components/helpers/InputSearch";
import {PayoutsEmpty} from "../components/PayoutItems/PayoutsEmpty";
import {Pagination} from "../components/General/Pagination";
import {ClipLoader} from "react-spinners";
import usePaginationHook from "../components/customHooks/usePaginationHook";
import {override} from "../data/overrride";
import {PaginationTypes} from "../components/types/PaginationTypes";
import {Badge} from "../components/ui/badge";

type payoutItemEachEntryProp = {
    'amount': string,
    'currency_code': string,
    'payout_batch_id': string,
    'payout_item_id': string,
    'affiliate_name': string,
    'receipient_wallet': string,
    'receiver_email': string,
    'receiver_number': string
    'sender_item_id': string,
    'transaction_status': string,
};

type payoutItemProps = PaginationTypes & {
    batch_payout_items: payoutItemEachEntryProp[]
}

export const BatchPayoutItem = () => {
    const [payoutItems, setPayoutItems] = useState<null | payoutItemProps>(null)
    const {localState} = useLocalState();
    const [loading, setLoading] = useState<boolean>(false)
    const [statusFilter, setStatusFilter] = useState<{ label: string, value: string }[]>([]);
    const {search, setSearch, searched, setIsSearched} = useInputSearch()
    const {
        handlePagination, updatePerPage,
        selectedLimit, perPage, currentPage
    } = usePaginationHook();
    const getItems = () => {
        setLoading(true)
        axiosClient.get('?action=wp_relay_paypal', {
            params: {
                method: 'paypal_batch_item_list',
                _wp_nonce_key: 'wpr_paypal_nonce',
                _wp_nonce: localState?.nonces?.wpr_paypal_nonce,
                search:search,
                per_page:perPage,
                current_page:currentPage
            },

        }).then((response) => {
            console.log(response)
            setPayoutItems(response.data.data)
        }).catch(response => {
            toastrError('Error Occurred')
        }).finally(() => {
            setLoading(false)
        })
    }

    React.useEffect(() => {
        getItems();
    }, [currentPage,perPage])

    return <div className='wrp-py-2'>
        <div className='wrp-flex wrp-justify-between wrp-my-4 wrp-mx-5'>
            <div
                className='wrp-flex wrp-justify-between lg:wrp-gap-8 wrp-items-center md:wrp-gap-8 wrp-gap-4'>
                    <span className='wrp-flex wrp-gap-2 wrp-items-center  '>
                        <span
                            className='lg:wrp-text-xl md:wrp-text-lg wrp-text-sm wrp-text-primary wrp-font-bold'>Payout Items</span>
                    </span>
            </div>
        </div>
        {!loading ? (<div className="wrp-bg-white wrp-h-full wrp-rounded-2xl wrp-p-4">
            <div className="wrp-flex wrp-flex-row wrp-justify-between wrp-items-center search-section wrp-py-2">
                <div>
                    <Select className="xl:wrp-min-w-350px"
                            placeholder='Filter by status' isMulti={true} styles={{
                        option: (styles, {data, isDisabled, isFocused, isSelected}) => {
                            return {
                                ...styles,
                                backgroundColor: isFocused ? "hsl(var(--primary))" : "hsl(var(--secondary))",
                                color: isFocused ? "hsl(var(--secondary))" : "hsl(var(--primary))"
                            };
                        }
                    }}
                            classNamePrefix="wrp-"
                            onChange={(selectedOption: any) => {
                                setStatusFilter(selectedOption)
                            }}
                        // options={[...OrderStatuses.successful, ...OrderStatuses.failure]}
                            defaultValue={statusFilter.length > 0 ? statusFilter : ''}
                    ></Select>
                </div>
                <InputSearch search={search} setSearch={setSearch} onclick={getItems}></InputSearch>
            </div>

            {
                searched && payoutItems?.batch_payout_items?.length == 0 ? (
                    <div
                        className="wrp-flex wrp-items-center wrp-flex-col wrp-justify-center wrp-text-center wrp-h-full">
                        <div className="wrp-mx-auto wrp-my-auto wrp-flex wrp-flex-col wrp-gap-5 wrp-p-5">
                            <div><i className="wpr wpr-list-empty wrp-text-6xl "></i></div>
                            <div><span className="wrp-text-lg wrp-font-bold">The sale detail you are looking for is not found</span>
                            </div>
                            <div>
                                <p className="wrp-text-sm ">Uh oh, your order list is looking a little empty!
                                    Looks like the search didn't return any results.</p>
                            </div>

                        </div>
                    </div>
                ) : !searched && payoutItems?.batch_payout_items?.length == 0 ? <PayoutsEmpty/> : (
                    <>
                        <div className="wrp-h-full">
                            <div className='wrp-flex wrp-flex-col wrp-gap-4'>
                                <div className='wrp-flex wrp-justify-between wrp-mt-5 wrp-w-full wrp-px-4'>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/4 wrp-text-2.5 wrp-uppercase'>Paypal Email
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/6 wrp-text-2.5 wrp-uppercase'>Amount
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/7 wrp-text-2.5 wrp-uppercase'>Transaction
                                        Status
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/7 wrp-text-2.5 wrp-uppercase'>Receipient
                                        Wallet
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/7 wrp-text-2.5 wrp-uppercase'>Payout
                                        Item Id
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/7 wrp-text-2.5 wrp-uppercase'>Sender
                                        Item Id
                                    </div>
                                    <div
                                        className=' wrp-text-grayprimary wrp-font-bold xl:wrp-text-xs md:wrp-text-2.5 wrp-w-1/7 wrp-text-2.5 wrp-uppercase'>Payout
                                        Batch Id
                                    </div>
                                </div>
                                <div className='wrp-flex wrp-flex-col wrp-gap-4'>
                                    {
                                        payoutItems?.batch_payout_items.map((payout: payoutItemEachEntryProp, index: any) => {
                                            return (

                                                <Card key={index}
                                                      className='wrp-flex wrp-justify-between wrp-p-4 !wrp-shadow-md wrp-h-18 wrp-items-center'>
                                                    <div
                                                        className="wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/4 ">#{payout.receiver_email}</div>
                                                    <div
                                                        className="wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/6 ">{payout.amount} {payout.currency_code}</div>
                                                    <div className='wrp-w-1/7'>
                                                        <Badge
                                                            className={`${payout.transaction_status!="SUCCESS" ? 'wrp-bg-red-600 hover:wrp-bg-red-600' : '!wrp-bg-green-600 hover:wrp-bg-green-600'} `}>{payout.transaction_status}</Badge>
                                                    </div>
                                                    <div
                                                        className="wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/7">{payout.receipient_wallet ? payout.receipient_wallet : '-'}</div>
                                                    <div
                                                        className='wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/7'>{payout.payout_item_id ? payout.payout_item_id : '-'}</div>
                                                    <div
                                                        className="wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/7">{payout.sender_item_id ? payout.sender_item_id : '-'}</div>
                                                    <div
                                                        className="wrp-text-primary xl:wrp-text-sm wrp-font-bold lg:wrp-text-xs md:wrp-text-2.5  wrp-text-2.5 wrp-w-1/7">{payout.payout_batch_id ? payout.payout_batch_id: '-'}</div>
                                                </Card>
                                            )
                                        })
                                    }
                                </div>
                            </div>
                            <div className='wrp-flex wrp-justify-end wrp-items-center wrp-my-4'>
                                <div className="pagination">
                                    <Pagination handlePageClick={handlePagination} updatePerPage={updatePerPage}
                                                selectedLimit={selectedLimit} pageCount={payoutItems?.total_pages || 1}
                                                limit={payoutItems?.per_page || 5} loading={false}
                                                forcePage={currentPage - 1}/>
                                </div>
                            </div>

                        </div>
                    </>
                )
            }
        </div>) : (<div className="wrp-h-[65vh] rwr-w-full wrp-flex">
            <ClipLoader className="wrp-text-primary" cssOverride={override}/>
        </div>)}
    </div>
}