import { useEffect, useState } from "react";

export default function Flash({ flash }) {
    const [visible, setVisible] = useState({ success: false, error: false });

    useEffect(() => {
        if (flash.success || flash.error) {
            setVisible({ success: !!flash.success, error: !!flash.error });
            const timer = setTimeout(() => {
                setVisible({ success: false, error: false });
            }, 3000);
            return () => clearTimeout(timer);
        }
    }, [flash]);

    return (
        <div className="absolute bottom-4 right-4 text-center">
            {visible.success && (
                <div className="p-2 rounded-md bg-green-400 min-w-64 text-white mt-4 shadow-lg transition-opacity duration-500">
                    {flash.success}
                </div>
            )}
            {visible.error && (
                <div className="p-2 rounded-md bg-red-500 min-w-64 text-white mt-4 shadow-lg transition-opacity duration-500">
                    {flash.error}
                </div>
            )}
        </div>
    );
}
