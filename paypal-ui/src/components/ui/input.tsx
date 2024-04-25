import * as React from "react"

import { cn } from "@/src/lib/utils"

export interface InputProps
  extends React.InputHTMLAttributes<HTMLInputElement> {}

const Input = React.forwardRef<HTMLInputElement, InputProps>(
  ({ className, type, ...props }, ref) => {
    return (
      <input
        type={type}
        className={cn(
          "wrp-flex wrp-h-10 wrp-w-full wrp-rounded-md wrp-border wrp-border-input wrp-bg-background wrp-px-3 wrp-py-2 wrp-text-sm wrp-ring-offset-background file:wrp-border-0 file:wrp-bg-transparent file:wrp-text-sm file:wrp-font-medium placeholder:wrp-text-muted-foreground focus-visible:wrp-outline-none focus-visible:wrp-ring-2 focus-visible:wrp-ring-ring focus-visible:wrp-ring-offset-2 disabled:wrp-cursor-not-allowed disabled:wrp-opacity-50",
          className
        )}
        ref={ref}
        {...props}
      />
    )
  }
)
Input.displayName = "Input"

export { Input }
