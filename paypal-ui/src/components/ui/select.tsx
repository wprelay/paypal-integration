import * as React from "react"
import * as SelectPrimitive from "@radix-ui/react-select"
import { Check, ChevronDown, ChevronUp } from "lucide-react"

import { cn } from "@/src/lib/utils"

const Select = SelectPrimitive.Root

const SelectGroup = SelectPrimitive.Group

const SelectValue = SelectPrimitive.Value

const SelectTrigger = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Trigger>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Trigger>
>(({ className, children, ...props }, ref) => (
  <SelectPrimitive.Trigger
    ref={ref}
    className={cn(
      "wrp-flex wrp-h-10 wrp-w-full wrp-items-center wrp-justify-between wrp-rounded-md wrp-border wrp-border-input wrp-bg-background wrp-px-3 wrp-py-2 wrp-text-sm wrp-ring-offset-background placeholder:wrp-text-muted-foreground focus:wrp-outline-none focus:wrp-ring-2 focus:wrp-ring-ring focus:wrp-ring-offset-2 disabled:wrp-cursor-not-allowed disabled:wrp-opacity-50 [&>span]:wrp-line-clamp-1",
      className
    )}
    {...props}
  >
    {children}
    <SelectPrimitive.Icon asChild>
      <ChevronDown className="wrp-h-4 wrp-w-4 wrp-opacity-50" />
    </SelectPrimitive.Icon>
  </SelectPrimitive.Trigger>
))
SelectTrigger.displayName = SelectPrimitive.Trigger.displayName

const SelectScrollUpButton = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.ScrollUpButton>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.ScrollUpButton>
>(({ className, ...props }, ref) => (
  <SelectPrimitive.ScrollUpButton
    ref={ref}
    className={cn(
      "wrp-flex wrp-cursor-default wrp-items-center wrp-justify-center wrp-py-1",
      className
    )}
    {...props}
  >
    <ChevronUp className="wrp-h-4 wrp-w-4" />
  </SelectPrimitive.ScrollUpButton>
))
SelectScrollUpButton.displayName = SelectPrimitive.ScrollUpButton.displayName

const SelectScrollDownButton = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.ScrollDownButton>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.ScrollDownButton>
>(({ className, ...props }, ref) => (
  <SelectPrimitive.ScrollDownButton
    ref={ref}
    className={cn(
      "wrp-flex wrp-cursor-default wrp-items-center wrp-justify-center wrp-py-1",
      className
    )}
    {...props}
  >
    <ChevronDown className="wrp-h-4 wrp-w-4" />
  </SelectPrimitive.ScrollDownButton>
))
SelectScrollDownButton.displayName =
  SelectPrimitive.ScrollDownButton.displayName

const SelectContent = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Content>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Content>
>(({ className, children, position = "popper", ...props }, ref) => (
  <SelectPrimitive.Portal>
    <SelectPrimitive.Content
      ref={ref}
      className={cn(
        "wrp-relative wrp-z-50 wrp-max-h-96 wrp-min-w-[8rem] wrp-overflow-hidden wrp-rounded-md wrp-border wrp-bg-popover wrp-text-popover-foreground wrp-shadow-md data-[state=open]:wrp-animate-in data-[state=closed]:wrp-animate-out data-[state=closed]:wrp-fade-out-0 data-[state=open]:wrp-fade-in-0 data-[state=closed]:wrp-zoom-out-95 data-[state=open]:wrp-zoom-in-95 data-[side=bottom]:wrp-slide-in-from-top-2 data-[side=left]:wrp-slide-in-from-right-2 data-[side=right]:wrp-slide-in-from-left-2 data-[side=top]:wrp-slide-in-from-bottom-2",
        position === "popper" &&
          "data-[side=bottom]:wrp-translate-y-1 data-[side=left]:wrp--translate-x-1 data-[side=right]:wrp-translate-x-1 data-[side=top]:wrp--translate-y-1",
        className
      )}
      position={position}
      {...props}
    >
      <SelectScrollUpButton />
      <SelectPrimitive.Viewport
        className={cn(
          "wrp-p-1",
          position === "popper" &&
            "wrp-h-[var(--radix-select-trigger-height)] wrp-w-full wrp-min-w-[var(--radix-select-trigger-width)]"
        )}
      >
        {children}
      </SelectPrimitive.Viewport>
      <SelectScrollDownButton />
    </SelectPrimitive.Content>
  </SelectPrimitive.Portal>
))
SelectContent.displayName = SelectPrimitive.Content.displayName

const SelectLabel = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Label>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Label>
>(({ className, ...props }, ref) => (
  <SelectPrimitive.Label
    ref={ref}
    className={cn("wrp-py-1.5 wrp-pl-8 wrp-pr-2 wrp-text-sm wrp-font-semibold", className)}
    {...props}
  />
))
SelectLabel.displayName = SelectPrimitive.Label.displayName

const SelectItem = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Item>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Item>
>(({ className, children, ...props }, ref) => (
  <SelectPrimitive.Item
    ref={ref}
    className={cn(
      "wrp-relative wrp-flex wrp-w-full wrp-cursor-default wrp-select-none wrp-items-center wrp-rounded-sm wrp-py-1.5 wrp-pl-8 wrp-pr-2 wrp-text-sm wrp-outline-none focus:wrp-bg-accent focus:wrp-text-accent-foreground data-[disabled]:wrp-pointer-events-none data-[disabled]:wrp-opacity-50",
      className
    )}
    {...props}
  >
    <span className="wrp-absolute wrp-left-2 wrp-flex wrp-h-3.5 wrp-w-3.5 wrp-items-center wrp-justify-center">
      <SelectPrimitive.ItemIndicator>
        <Check className="wrp-h-4 wrp-w-4" />
      </SelectPrimitive.ItemIndicator>
    </span>

    <SelectPrimitive.ItemText>{children}</SelectPrimitive.ItemText>
  </SelectPrimitive.Item>
))
SelectItem.displayName = SelectPrimitive.Item.displayName

const SelectSeparator = React.forwardRef<
  React.ElementRef<typeof SelectPrimitive.Separator>,
  React.ComponentPropsWithoutRef<typeof SelectPrimitive.Separator>
>(({ className, ...props }, ref) => (
  <SelectPrimitive.Separator
    ref={ref}
    className={cn("wrp--mx-1 wrp-my-1 wrp-h-px wrp-bg-muted", className)}
    {...props}
  />
))
SelectSeparator.displayName = SelectPrimitive.Separator.displayName

export {
  Select,
  SelectGroup,
  SelectValue,
  SelectTrigger,
  SelectContent,
  SelectLabel,
  SelectItem,
  SelectSeparator,
  SelectScrollUpButton,
  SelectScrollDownButton,
}
