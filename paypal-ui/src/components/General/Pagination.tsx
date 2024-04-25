import ReactPaginate from "react-paginate";
import React, { useState } from "react";
import Select from "react-select";
import { paginationData } from "../../data/PaginationDefault";

interface PaginationProps {
    handlePageClick: (data: any) => void,
    pageCount: number,
    limit: number,
    forcePage?: number,
    loading: boolean,
    updatePerPage?: (limit: number) => void
    selectedLimit?: number,
    hidePerPageSelect?: boolean
}

export const Pagination = ({
                               handlePageClick,
                               pageCount,
                               forcePage,
                               loading,
                               updatePerPage,
                               selectedLimit,
                               hidePerPageSelect = false
                           }: PaginationProps) => {


    const [dropdownOpen, setDropdownOpen] = useState(false);
    const pages = [
        { 'label': '5', 'value': 5 },
        { 'label': '10', 'value': 10 },
        { 'label': '20', 'value': 20 },
        { 'label': '100', 'value': 100 },
    ]

    const getSelectedOption = (selectedLimit: number) => {

        return pages.filter((item: any) => {
            return item.value == selectedLimit
        })[0];
    }


    return (<div className="wrp-flex wrp-flex-row wrp-justify-end wrp-gap-6">

        {hidePerPageSelect ? null : (
            <div
                className="wrp-flex wrp-justify-center wrp-items-center wrp-gap-3">

                <Select styles={{
                    option: (styles, { data, isDisabled, isFocused, isSelected }) => {
                        return {
                            ...styles,
                            backgroundColor: isFocused ? "hsl(var(--primary))" : "hsl(var(--secondary))",
                            color: isFocused ? "hsl(var(--secondary))" : "hsl(var(--primary))"
                        };
                    }
                }}
                        classNamePrefix="wrp-"
                        onChange={(selectedOption: any) => {
                            updatePerPage ? updatePerPage(selectedOption.value) : null
                        }}
                        options={pages}
                        defaultValue={getSelectedOption(selectedLimit ?? paginationData.per_page)}
                ></Select>
            </div>
        )}
        <div>
            <ReactPaginate
                previousLabel={
                    <i className="wpr wpr-back-arrow wrp-w-9 wrp-h-9 wrp-flex wrp-justify-center wrp-items-center hover:wrp-bg-light-gray"></i>
                }
                nextLabel={
                    <i className="wpr wpr-forward-arrow wrp-w-9 wrp-h-9 wrp-flex wrp-justify-center wrp-items-center hover:wrp-bg-light-gray"></i>
                }

                // prevPageRel={null}

                breakLabel={"..."}
                pageCount={pageCount}
                forcePage={forcePage}
                // marginPagesDisplayed={1}
                pageRangeDisplayed={3}

                onPageChange={handlePageClick}
                breakClassName={""}

                breakLinkClassName={"wrp-shadow-none wrp-outline-none wrp-border-0"}

                // previousClassName={`${loading && "wrp-pointer-events-none"}`}

                nextClassName={`${loading && "wrp-pointer-events-none"}`}
                disabledClassName="wrp-disabled"

                // onPageActive={true}
                pageLinkClassName={
                    'wrp-px-4 wrp-py-3 wrp-outline-none hover:wrp-bg-secondary '
                }

                activeLinkClassName={
                    'wrp-text-secondary wrp-outline-none  wrp-rounded wrp-border wrp-bg-primary  '
                }

                activeClassName={''}

                containerClassName={
                    "wrp-flex wrp-items-center wrp-justify-end wrp-gap-1.5"
                }
            />
        </div>

    </div>)
};