export default function Flash({ flash }) {
    return (
        <div>
            {flash.success && (
                <div className="p-2 rounded-md bg-green-400 min-w-64 text-white mt-4">
                    {flash.success}
                </div>
            )}
            {flash.error && (
                <div className="p-2 rounded-md bg-red-500 min-w-64 text-white mt-4">
                    {flash.error}
                </div>
            )}
        </div>
    );
}
