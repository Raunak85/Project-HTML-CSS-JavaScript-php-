function renderJobCards(jobs) {
    const jobListings = document.querySelector('.job-listings');
    jobListings.innerHTML = '';

    jobs.forEach(job => {
        const jobCard = document.createElement('div');
        jobCard.classList.add('job-card');

        jobCard.innerHTML = `
            <h2>${job.title}</h2>
            <p><strong>Company:</strong> ${job.company_name}</p>
            <p>${job.description}</p>
            <p><strong>Location:</strong> ${job.location}</p>
            <p><strong>Posted Date:</strong> ${job.vacancy_date}</p>
            <a href="../html/apply.php?job_id=${job.job_id}" class="apply-button">Apply</a>
        `;

        jobListings.appendChild(jobCard);
    });
}
