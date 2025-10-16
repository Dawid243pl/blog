import { router } from "@inertiajs/react";
import { XMarkIcon } from "@heroicons/react/24/outline";

export default function Comment({ comment, auth, postOwner }) {
    const handleDelete = (commentId) => {
        if (confirm("Are you sure you want to delete this comment?")) {
            router.delete(route("comment.destroy", commentId));
        }
    };

    return (
        <div
            key={comment.id}
            className="mb-2 p-2 border rounded bg-gray-50 flex justify-between items-start"
        >
            <div>
                <p>{comment.comment}</p>
                <p className="text-sm text-gray-600">
                    By {comment?.user?.name ?? "Guest"}
                </p>
            </div>
            {auth.user &&
                (auth.user.id === comment.user_id ||
                    auth.user.id === postOwner.id ||
                    auth.admin) && (
                    <XMarkIcon
                        className="h-4 w-4 text-red-500 cursor-pointer ml-auto shrink-0"
                        onClick={() => handleDelete(comment.id)}
                    />
                )}
        </div>
    );
}
