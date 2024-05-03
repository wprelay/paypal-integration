import * as React from "react"
import * as SwitchPrimitives from "@radix-ui/react-switch"

import { cn } from "@/src/lib/utils"

const Switch = React.forwardRef<
  React.ElementRef<typeof SwitchPrimitives.Root>,
  React.ComponentPropsWithoutRef<typeof SwitchPrimitives.Root>
>(({ className, ...props }, ref) => (
  <SwitchPrimitives.Root
    className={cn(
      "wrp-peer wrp-inline-flex wrp-h-6 wrp-w-11 wrp-shrink-0 wrp-cursor-pointer wrp-items-center wrp-rounded-full wrp-border-2 wrp-border-transparent wrp-transition-colors focus-visible:wrp-outline-none focus-visible:wrp-ring-2 focus-visible:wrp-ring-ring focus-visible:wrp-ring-offset-2 focus-visible:wrp-ring-offset-background disabled:wrp-cursor-not-allowed disabled:wrp-opacity-50 data-[state=checked]:wrp-bg-primary data-[state=unchecked]:wrp-bg-input",
      className
    )}
    {...props}
    ref={ref}
  >
    <SwitchPrimitives.Thumb
      className={cn(
        "wrp-pointer-events-none wrp-block wrp-h-5 wrp-w-5 wrp-rounded-full wrp-bg-background wrp-shadow-lg wrp-ring-0 wrp-transition-transform data-[state=checked]:wrp-translate-x-5 data-[state=unchecked]:wrp-translate-x-0"
      )}
    />
  </SwitchPrimitives.Root>
))
Switch.displayName = SwitchPrimitives.Root.displayName

export { Switch }
