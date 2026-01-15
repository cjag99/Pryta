<?php
enum TaskState: string
{
    case NOT_ASSIGNED = "Not assigned";
    case PENDING = "Pending";
    case ON_REVIEW = "On review";
    case FINISHED = "Finished";
}
