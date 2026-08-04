# Project Design & Development Guidelines

This document contains rules, design principles, and constraints for AI coding assistants working on the Booking Platform workspace.

## 1. VADEL Minimalist Luxury Sports Aesthetic
- **High-Contrast Dark Theme**: Use dark, moody backgrounds (`bg-slate-950`, `bg-slate-900`) paired with high-contrast imagery and crisp white typography.
- **Massive Display Typography**: Use bold, italic, uppercase display titles (`font-black italic tracking-tighter uppercase`) for hero headlines.
- **Floating Card Containers**: Frame key sections and heroes inside floating card containers with deep, smooth rounded corners (`rounded-[2rem]` or `rounded-[2.5rem]`).
- **Subpage Consistency**: All subpages (e.g. Features, Solutions, Live Venues, Pricing) must mirror the Home page's VADEL aesthetic, dark hero canvas, and Liquid Glass styling while preserving their original content structure and core modules.

## 2. Liquid Glass UI Principles
- **Multi-Tiered Liquid Glass Layers**: Use deep multi-tiered backdrop blur filters (`backdrop-blur-xl`, `backdrop-blur-2xl`, `backdrop-blur-3xl`) over translucent liquid glass surfaces (`bg-white/5`, `bg-white/10`, `bg-white/15`).
- **Specular Edge Highlights & Light Refraction**: Apply physical top specular glare highlights (`shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]`) on liquid glass cards, buttons, badges, and input elements to simulate realistic fluid glass refraction.
- **Liquid Glass Interactive Controls**: Style form inputs and controls with translucent liquid glass surfaces (`bg-white/10 backdrop-blur-2xl border border-white/20`) and liquid specular focus glow (`focus:bg-white/20 focus:border-white/60`).
- **Balanced Overlay Opacities**: Keep background gradient masks semi-transparent (never dark enough to obscure background imagery) so physical venue photography shines through liquid glass layers.

## 3. Strict Anti-Pattern Rules (No Cheap AI Tropes, Emojis, or Floating Tag Pills)
- **No Cliché Marketing Buzzwords**: NEVER add cliché AI tropes such as "Next-Gen", "Cutting-Edge", "State-of-the-Art", or generic marketing hype badges.
- **No Pulsing Dots**: NEVER add pulsing dots (`animate-pulse`) or artificial status indicators in hero sections or buttons.
- **No Emojis in User Interface**: DO NOT use emojis (e.g. ⚡, 📅, 🎟️) as UI icons or section headers. ALWAYS use crisp inline SVG vector icons instead.
- **No Floating Tag Pills Above Titles**: NEVER add artificial uppercase floating tag pills (e.g. "Platform Architecture", "Get Started", "Get in Touch", "Operating Network") above section titles. Keep headlines clean, bold, and un-cluttered.
- **No Unnecessary Status Badges**: NEVER add artificial status pills (e.g. "Active Venue", "Active Site") or colored status dots in card corners/tables. Keep UI clean and un-cluttered.
- **Admin Control Panel Ergonomics**: Super-Admin & Tenant Admin pages must use spacious, clean operational headers (no oversized marketing subhero image canvases). Tables must be high-density, clean B2B layouts without decorative status dots.
- **Use Modern Tailwind CSS v4 Utilities**: Always use Tailwind v4 standards: `bg-linear-to-t/b/r/br` (instead of deprecated `bg-gradient-to-*`), standard rounded scale (`rounded-3xl`, `rounded-4xl`, `rounded-5xl`), and standard spacing scale (`h-115`, `min-h-135`, `max-h-230`).

## 4. Navbar & Header Integrity
- **Seamless Merge on Top Alignment**: Navbar must merge seamlessly into the page background when top-aligned (`bg-slate-50 py-1`) with no bottom borders (`border-b`) or heavy drop shadows (`shadow`).
- **Original Brand Logo**: Always use the original `/images/logo.png` image mark.
- **Standard B2B Link Names**: Name navbar items properly: `Home`, `Features`, `Solutions`, `Live Venues`, `Pricing`, `Contact`.
- **No Admin Pollution**: Do NOT add sign-out forms or administrative badges to the tenant/parent marketing header.

## 5. Hero Viewport Fitting
- **100% Single Viewport Fit**: Hero section canvas must fit 100% within the visible viewport on page load without requiring vertical scrolling or fullscreen mode (`h-[calc(100vh-4.5rem)]`).

## 6. Venue Showcase Directory
- **Strict 4-Company Showcase Limit**: The "Trusted by Growing Sports Venues" section must strictly display a maximum of 4 featured companies in a single 4-column row layout on desktop (`limit(4)` / `take(4)`).
- **Interactive Category Filtering**: Provide an interactive filter bar (`All Venues`, `Badminton & Squash`, `Padel & Racquet`, `Gym & Fitness`) with live JS/Alpine category toggling.
- **Category-Matched Imagery**: Display high-resolution category-matched cover images (`badminton.png`, `padel_tennis_arena.png`, `gym_fitness_studio.png`, `vadel_hero_court.png`) for each venue card.

## 7. Intentional B2B Copywriting
- **Venue Operator Focus**: All copy and text must directly communicate the app's value proposition to venue owners and managers (B2B audience).
- **Core Feature Terms**: Focus explicitly on court automation, multi-court day matrices, IoT floodlight relays, peak pricing surcharges, headcount capacity caps, and digital member credit passes.
