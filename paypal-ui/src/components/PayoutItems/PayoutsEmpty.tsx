import React from "react";

export const PayoutsEmpty = () => {
    return (
        <div className="wrp-flex wrp-items-center wrp-flex-col wrp-justify-center wrp-text-center wrp-h-full">
            <div className="wrp-mx-auto wrp-my-auto wrp-flex wrp-flex-col wrp-gap-5 wrp-p-5">
                <div><i className="rwp rwp-list-empty wrp-text-6xl "></i></div>
                <div><span className="wrp-text-lg wrp-font-bold">No Payouts Yet</span></div>
                <div>
                    <p className="wrp-text-sm ">Uh oh, Your Payouts list is looking a little empty! Time to add some new ones.</p>
                </div>
            </div>
        </div>
    )
}