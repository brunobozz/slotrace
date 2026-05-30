import { Outfit, JetBrains_Mono } from "next/font/google";
import "./globals.css";
import { AuthProvider } from "./context/AuthContext";
import AuthWrapper from "./components/AuthWrapper";

import { LanguageProvider } from "@/context/LanguageContext";

const outfit = Outfit({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700", "800"],
  variable: "--font-outfit",
});

const jetbrainsMono = JetBrains_Mono({
  subsets: ["latin"],
  weight: ["400", "700"],
  variable: "--font-mono-telemetry",
});

export const metadata = {
  title: "Slotrace | Slotcar Telemetry & Management",
  description: "Intelligent telemetry dashboard, lap times, and Slotcar championship management.",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en" suppressHydrationWarning className={`${outfit.variable} ${jetbrainsMono.variable} h-full antialiased dark`}>
      <body suppressHydrationWarning className="min-h-full flex flex-col bg-[#090b11] text-slate-100 selection:bg-cyan-500 selection:text-black">
        <AuthProvider>
          <LanguageProvider>
            <AuthWrapper>{children}</AuthWrapper>
          </LanguageProvider>
        </AuthProvider>
      </body>
    </html>
  );
}
