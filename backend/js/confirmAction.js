function confirmDelete(status) {
    if (status == 0) {
        return confirm('Are you sure you want to delete this record?');
    } else {
        alert('You cannot delete this record because it\'s already subdivided/titled. Please delete the subdivided entry first.');
        return false;
    }
}

function confirmSubdivide(status) {
    if (status == 'untitled') {
        return confirm('Are you sure you want to subdivide this record?');
    } else {
        alert('You cannot subdivide this record because it\'s already titled. Please edit the subdivided entry first.');
        return false;
    }
}
