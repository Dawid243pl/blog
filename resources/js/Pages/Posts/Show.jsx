import { Head, Link, router } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import Textarea from "@/Components/Textarea";
import PostLayout from "@/Components/Posts/PostLayout";

export default function Show({ auth, post }) {
    const {
        data,
        setData,
        post: postData,
        processing,
        errors,
    } = useForm({
        comment: "",
    });

    function submit(e, postId) {
        e.preventDefault();

        postData(
            route("comment.store", postId),
            {
                preserveScroll: true,
                onSuccess: () => setData("comment", ""),
            }
        );
        setData("comment", "");
    }

    return (
        <div className="max-w-7xl mx-auto p-6">
            <Link
                href={route("post.index")}
                method="get"
                as="button"
                className="mb-4 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
            >
                Back to Posts
            </Link>
            <PostLayout auth={auth} post={post} listView={false}></PostLayout>
        </div>
    );
}
